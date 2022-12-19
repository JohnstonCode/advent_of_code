package main

import (
	"fmt"
	"os"
	"strings"
)

type RobotCost struct {
	ore      int
	clay     int
	obsidian int
}

type Blueprint struct {
	oreRobot      RobotCost
	clayRobot     RobotCost
	obsidianRobot RobotCost
	geodeRobot    RobotCost
}

func main() {
	content, _ := os.ReadFile("./input.txt")
	blueprints := getBlueprints(string(content))
	part1 := 0
	part2 := 1

	for i, blueprint := range blueprints {
		part1 += (i + 1) * maxGeodesOpened(blueprint, 24)
	}

	for _, blueprint := range blueprints[:3] {
		part2 *= maxGeodesOpened(blueprint, 32)
	}

	fmt.Printf("Part 1: %v\n", part1)
	fmt.Printf("Part 2: %v\n", part2)
}

func maxGeodesOpened(blueprint Blueprint, time int) int {
	type queueItem struct {
		time           int
		ore            int
		clay           int
		obsidian       int
		geode          int
		oreRobots      int
		clayRobots     int
		obsidianRobots int
		geodeRobots    int
	}

	max := 0
	queue := []queueItem{{time: 0, oreRobots: 1}}
	maxOreNeeded := MaxIntInSlice([]int{blueprint.oreRobot.ore, blueprint.clayRobot.ore, blueprint.obsidianRobot.ore, blueprint.geodeRobot.ore})
	maxClayNeeded := MaxIntInSlice([]int{blueprint.oreRobot.clay, blueprint.clayRobot.clay, blueprint.obsidianRobot.clay, blueprint.geodeRobot.clay})
	maxObsidianNeeded := MaxIntInSlice([]int{blueprint.oreRobot.obsidian, blueprint.clayRobot.obsidian, blueprint.obsidianRobot.obsidian, blueprint.geodeRobot.obsidian})

	seen := map[queueItem]bool{}

	for len(queue) > 0 {
		item := queue[0]
		queue = queue[1:]

		if seen[item] {
			continue
		}

		seen[item] = true

		if item.time == time {
			max = Max(max, item.geode)

			continue
		}

		ore := item.oreRobots
		clay := item.clayRobots
		obsidian := item.obsidianRobots
		geode := item.geodeRobots

		if item.ore >= blueprint.geodeRobot.ore && item.obsidian >= blueprint.geodeRobot.obsidian {
			queue = append(queue, queueItem{
				ore:            item.ore - blueprint.geodeRobot.ore + ore,
				clay:           item.clay + clay,
				obsidian:       item.obsidian - blueprint.geodeRobot.obsidian + obsidian,
				geode:          item.geode + geode,
				time:           item.time + 1,
				oreRobots:      item.oreRobots,
				clayRobots:     item.clayRobots,
				obsidianRobots: item.obsidianRobots,
				geodeRobots:    item.geodeRobots + 1,
			})

			continue
		}

		if item.ore >= blueprint.obsidianRobot.ore && item.clay >= blueprint.obsidianRobot.clay && item.obsidianRobots < maxObsidianNeeded {
			queue = append(queue, queueItem{
				ore:            item.ore - blueprint.obsidianRobot.ore + ore,
				clay:           item.clay - blueprint.obsidianRobot.clay + clay,
				obsidian:       item.obsidian + obsidian,
				geode:          item.geode + geode,
				time:           item.time + 1,
				oreRobots:      item.oreRobots,
				clayRobots:     item.clayRobots,
				obsidianRobots: item.obsidianRobots + 1,
				geodeRobots:    item.geodeRobots,
			})

			continue
		}

		if item.ore >= blueprint.oreRobot.ore && item.oreRobots < maxOreNeeded {
			queue = append(queue, queueItem{
				ore:            item.ore - blueprint.oreRobot.ore + ore,
				clay:           item.clay + clay,
				obsidian:       item.obsidian + obsidian,
				geode:          item.geode + geode,
				time:           item.time + 1,
				oreRobots:      item.oreRobots + 1,
				clayRobots:     item.clayRobots,
				obsidianRobots: item.obsidianRobots,
				geodeRobots:    item.geodeRobots,
			})
		}

		if item.ore >= blueprint.clayRobot.ore && item.clayRobots < maxClayNeeded {
			queue = append(queue, queueItem{
				ore:            item.ore - blueprint.clayRobot.ore + ore,
				clay:           item.clay + clay,
				obsidian:       item.obsidian + obsidian,
				geode:          item.geode + geode,
				time:           item.time + 1,
				oreRobots:      item.oreRobots,
				clayRobots:     item.clayRobots + 1,
				obsidianRobots: item.obsidianRobots,
				geodeRobots:    item.geodeRobots,
			})
		}

		queue = append(queue, queueItem{
			ore:            item.ore + ore,
			clay:           item.clay + clay,
			obsidian:       item.obsidian + obsidian,
			geode:          item.geode + geode,
			time:           item.time + 1,
			oreRobots:      item.oreRobots,
			clayRobots:     item.clayRobots,
			obsidianRobots: item.obsidianRobots,
			geodeRobots:    item.geodeRobots,
		})
	}

	return max
}

func getBlueprints(content string) []Blueprint {
	var blueprints []Blueprint

	for _, line := range strings.Split(content, "\n") {
		var blueprint Blueprint
		var i int

		_, _ = fmt.Sscanf(
			line,
			"Blueprint %d: Each ore robot costs %d ore. Each clay robot costs %d ore. Each obsidian robot costs %d ore and %d clay. Each geode robot costs %d ore and %d obsidian.",
			&i,
			&blueprint.oreRobot.ore,
			&blueprint.clayRobot.ore,
			&blueprint.obsidianRobot.ore,
			&blueprint.obsidianRobot.clay,
			&blueprint.geodeRobot.ore,
			&blueprint.geodeRobot.obsidian,
		)

		blueprints = append(blueprints, blueprint)
	}

	return blueprints
}

func Max(a, b int) int {
	if a > b {
		return a
	}

	return b
}

func MaxIntInSlice(a []int) int {
	max := a[0]

	for _, v := range a {
		max = Max(max, v)
	}

	return max
}
